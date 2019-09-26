const amqp = require('amqplib/callback_api')
const Request = require("request")
const Kudos = require('../controllers/kudos.controller.js');
const configuracionRabbit = require('../../config/rabbit.config.js')
const restUsuario = require('../../config/external.config.js')

module.exports = (app) => {
    app.get('/stats/restar', (req, res) => {
        amqp.connect(configuracionRabbit.url, function (error0, connection) {
            connection.createChannel(function (error1, channel) {
                channel.assertQueue(configuracionRabbit.queueStatsDel, {
                    durable: true
                })
                channel.prefetch(1)
                channel.consume(configuracionRabbit.queueStatsDel, function reply(msg) {
                    var n = msg.content.toString()
                    var r = bajarQty(n)
                    channel.sendToQueue(msg.properties.replyTo,
                        Buffer.from("OK"), {
                            correlationId: msg.properties.correlationId
                        })
                    channel.ack(msg)
                })

            })
        })
        res.send("Se restaron los kudos")
    })

    app.get('/stats/sumar', (req, res) => {
        amqp.connect(configuracionRabbit.url, function (error0, connection) {
            connection.createChannel(function (error1, channel) {
                channel.assertQueue(configuracionRabbit.queueStats, {
                    durable: true
                })
                channel.prefetch(1)
                channel.consume(configuracionRabbit.queueStats, function reply(msg) {
                    var n = msg.content.toString()
                    var r = sumarQty(n)
                    channel.sendToQueue(msg.properties.replyTo,
                        Buffer.from("sumar cantidad de kudos"), {
                            correlationId: msg.properties.correlationId
                        })
                    channel.ack(msg)
                })

            })
        })

        amqp.connect(configuracionRabbit.url, function (error0, connection) {
            connection.createChannel(function (error1, channel) {
                channel.assertQueue(configuracionRabbit.queueDelUser, {
                    durable: true
                })
                channel.prefetch(1)
                channel.consume(configuracionRabbit.queueDelUser, function reply(msg) {
                    var n = msg.content.toString()
                    var r = Kudos.delAllKudos(n)
                    channel.sendToQueue(msg.properties.replyTo,
                        Buffer.from("Restar cantidad"), {
                            correlationId: msg.properties.correlationId
                        })
                    channel.ack(msg)
                })

            })
        })
        res.send("Kudos sumado")
    })

    function sumarQty(n) {
        Request.get(restUsuario.url + restUsuario.sumarQty + n, (error, response, body) => { })
    }
    function bajarQty(n) {
        Request.get(restUsuario.url + restUsuario.bajarQty + n, (error, response, body) => { })
    }
}
