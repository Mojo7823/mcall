const http = require('http');
const express = require('express');
const WebSocket = require('ws');
const app = express();

app.use(express.json());

const wss = new WebSocket.Server({ noServer: true });

wss.broadcast = function broadcast(data) {
    wss.clients.forEach(function each(client) {
        if (client.readyState === WebSocket.OPEN) {
            client.send(data);
        }
    });
};

wss.on('connection', ws => {
    console.log('Client connected');
    ws.on('message', function incoming(data) {
        // Broadcast to everyone else.
        wss.clients.forEach(function each(client) {
            if (client !== ws && client.readyState === WebSocket.OPEN) {
                client.send(data);
            }
        });
    });
});

app.post('/update', (req, res) => {
    wss.broadcast(JSON.stringify(req.body));
    res.send('Updated');
});

const server = http.createServer(app);

server.on('upgrade', function upgrade(request, socket, head) {
    wss.handleUpgrade(request, socket, head, function done(ws) {
        wss.emit('connection', ws, request);
    });
});

server.listen(8080, () => {
    console.log('HTTP and WebSocket server started on port 8080');
});