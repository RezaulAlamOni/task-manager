var express = require('express'),
    app = express(),
    // XMLHttpRequest = require('xmlhttprequest').XMLHttpRequest
    // xhs = new XMLHttpRequest();
    // fs = require('fs');
    // chalk = require('chalk');

    // privateKey = fs.readFileSync('/var/www/html/cert/y2mp3/privetkey.pem','utf8');
    // certificate = fs.readFileSync('/var/www/html/cert/y2mp3/cert.pem','utf8');
    // credential = {key : privateKey, cert : certificate}
    // server = https.createServer(credential,app),


    https =  require('http'),//for http server
    // https =  require('https'),//for https server
    server = https.createServer(app),
    io = require('socket.io')(server),
    port = 3000,
    ipAddress = '0.0.0.0';
server.listen(port,ipAddress,function () {
    console.log('Chat Server started on port : ' + port);
});
app.get('/', function (req, res) {
    res.sendFile(__dirname + '/package.json');
});
// =============================
// Connections
// =============================

io.on('connection', function(socket) {
    console.log('New Connection');
    socket.on('loginId', function(data) {
        console.log('Login success'+data);
        io.emit('newMessage','Login Success and socket server connected');
    });

    socket.on('assignUser', function(data) {
        io.emit('assign_user',data);
    });
    socket.on('rulesCreate', function(data) {
        io.emit('rulesCreateAndAssign',data);
    });

    socket.on('CardMoved', function(data) {
        io.emit('CardMoved',data);
    });

    socket.on('taskUpdate', function(data) {
        console.log(data)
        io.emit('takUpdateSocket',data);
    });

});
