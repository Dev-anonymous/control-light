module.exports = {
    validate_connect: function (data, socket) {
        if (typeof data != "object") {
            socket.disconnect();
            return;
        }

        if (!data?.uid || !data.id || !data.type) {
            socket.disconnect();
            return;
        }
    },
};
