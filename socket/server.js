const express = require("express");
const bodyParser = require("body-parser");
const promis = require("util");
const app = express();
var mysql = require("mysql");

const util = require("./utils/func.js");
const e = require("express");

const http = require("http").createServer(app);
const socketIO = require("socket.io")(http, { cors: { origin: "*" } });
var users = [];
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));

var connection = mysql.createConnection({
    host: "localhost",
    user: "root",
    password: "",
    database: "stock",
    charset: "utf8mb4",
});

socketIO.on("connection", (socket) => {
    socket.on("connected", async (data) => {
        util.validate_connect(data, socket);
        connection.query(
            "select * from users where id=?",
            [data?.uid],
            function (error, results) {
                if (results?.length > 0) {
                    initEvent(socket, data);
                } else {
                    socket.disconnect();
                }
            }
        );
    });
});

async function initEvent(socket, data) {
    data.socketId = socket.id;

    if (data.type == "app") {
        var m = users;
        users = users.filter(function (value, index, arr) {
            return value?.id != data?.id;
        });
        m = m.filter(function (value, index, arr) {
            return value?.uid == data?.uid && value?.type == "user";
        });
        var mid;
        if (m.length > 0) {
            mid = m[0]?.socketId;
        }
        socketIO.to(mid).emit("app-connected", m.length > 0);
        socketIO.to(socket.id).emit("app-connected", m.length > 0);
    } else {
        var m = users;
        users = users.filter(function (value, index, arr) {
            return value?.id != data?.id;
        });
        m = m.filter(function (value, index, arr) {
            return value?.uid == data?.uid && value?.type == "app";
        });
        var mid;
        if (m.length > 0) {
            mid = m[0]?.socketId;
        }
        socketIO.to(mid).emit("app-connected", m.length > 0);
        socketIO.to(socket.id).emit("app-connected", m.length > 0);
    }
    users.push(data);
    socketIO.to(socket.id).emit("welcome", "Hi !");

    socket.on("new-item", async (data) => {
        if (data?.uid && data?.type && data?.code) {
            if (data.type == "app") {
                connection.query(
                    "SELECT article.id, article, devise, prix, reduction FROM article join devise on devise.id=article.devise_id where code=?",
                    [data?.code],
                    function (error, results) {
                        if (error) return;
                        if (results.length == 0) {
                            socketIO.to(socket.id).emit("message", {
                                ok: false,
                                message: "Code barre non valide",
                            });
                            return;
                        }
                        var results = results[0];
                        var m = users;
                        m = m.filter(function (value, index, arr) {
                            return (
                                value?.uid == data?.uid && value?.type == "user"
                            );
                        });

                        var mid;
                        if (m.length > 0) {
                            mid = m[0]?.socketId;
                        }

                        var prix = results.prix;
                        var reduction = results.reduction;
                        var red = prix - prix * (reduction / 100);
                        var prix_min = reduction > 0 ? red : prix;

                        var res = {
                            id: results.id,
                            article: results.article,
                            prix: prix + " " + results.devise,
                            reduction: reduction,
                            prix_min: prix_min + " " + results.devise,
                            pv: prix + " " + results.devise,
                        };

                        socketIO.to(mid).emit("new-item", res);
                        socketIO.to(socket.id).emit("message", {
                            ok: true,
                            message:
                                "Article : " +
                                results.article +
                                "(" +
                                results.prix +
                                " " +
                                results.devise +
                                ")",
                        });
                    }
                );
            }
        }
    });

    socket.on("disconnect", async () => {
        var d = users;
        d = d.filter(function (value) {
            return value?.socketId == socket.id;
        });
        users = users.filter(function (value, index, arr) {
            return value?.socketId != socket.id;
        });
        if (d.length > 0) {
            d = d[0];
            if (d?.type == "app") {
                var m = users;
                m = m.filter(function (value, index, arr) {
                    return value?.uid == d?.uid && value?.type == "user";
                });
                if (m.length > 0) {
                    m = m[0];
                    socketIO.to(m?.socketId).emit("app-connected", false);
                }
            } else {
                var m = users;
                m = m.filter(function (value, index, arr) {
                    return value?.uid == d?.uid && value?.type == "app";
                });
                if (m.length > 0) {
                    m = m[0];
                    socketIO.to(m?.socketId).emit("app-connected", false);
                }
            }
        }
    });
}

http.listen(3000, "0.0.0.0", function () {
    console.log("Server started ... ");
});
