const amqp = require('amqplib/callback_api')
const configuracionRabit = require('../../config/rabbit.config.js')
const Kudos = require('../models/kudos.model.js');
const _self = this;

exports.create = async (req, res) => {
    var queueUser = "QUEUE-"
    if (!req.body) {
        return res.status(400).send({
            message: "kudos vacio!!!"
        })
    }
    const kudos = new Kudos({
        de: req.body.de,
        nombreDe: req.body.nombreDe,
        para: req.body.para,
        nombrePara: req.body.nombrePara,
        tema: req.body.tema,
        lugar: req.body.lugar,
        texto: req.body.text
    })
    await kudos.save()
    queueUser = "QUEUE-" + kudos.destino

    // RABBIT SUMA LOS KUDOS CON AYUDA DEL STATS
    amqp.connect(configuracionRabit.url, function (error0, connection) {
        connection.createChannel(function (error1, channel) {
            channel.assertQueue('', {
                exclusive: true
            }, (err, q) => {
                if (err)
                    throw err

                var correlationId = uidCola()
                var de = kudos.de
                channel.consume(q.queue, (msg) => {
                    if (msg.properties.correlationId == correlationId) {
                    }
                }, { noAck: true })
                channel.sendToQueue(configuracionRabit.rpcSumar,
                    Buffer.from(de.toString()), {
                        correlationId: correlationId,
                        replyTo: q.queue
                    })
            })
        })
    })
    // END RABBIT

    // RABBIT PUBLICA KUDOS DEL USUARIO
    amqp.connect(configuracionRabit.url, (error0, connection) => {
        connection.createChannel((error1, channel) => {
            channel.assertQueue(queueUser, {
                durable: true
            })
            channel.prefetch(1)
            channel.consume(queueUser, async function reply(msg) {
                var n = msg.content.toString()
                var r = await _self.findRabbitKudos(n)
                channel.sendToQueue(msg.properties.replyTo,
                    Buffer.from(JSON.stringify(r)), {
                        correlationId: msg.properties.correlationId
                    })
                channel.ack(msg)
            })
        })
    })
    // FIN RABBIT    

    res.redirect('/stats/sumar')
}
exports.delAllKudos = async (id) => {
    const result = await Kudos.remove({ para: id })

}

exports.delete = async (req, res) => {
    const { _id } = req.params
    const kudosReg = await Kudos.findById(_id)

    await Kudos.findOneAndDelete(_id)
    amqp.connect(configuracionRabit.url, function (error0, connection) {
        connection.createChannel(function (error1, channel) {
            channel.assertQueue('', {
                exclusive: true
            }, (err, q) => {
                if (err)
                    throw err
                var correlationId = uidCola()
                var de = kudosReg.de
                channel.consume(q.queue, (msg) => {
                    if (msg.properties.correlationId == correlationId) {
                    }
                }, { noAck: true })
                channel.sendToQueue(configuracionRabit.rpcBorrar,
                    Buffer.from(de.toString()), {
                        correlationId: correlationId,
                        replyTo: q.queue
                    })
            })
        })
    })
    // END RABBIT
    res.redirect('/stats/restar')
}


exports.findAll = async (req, res) => {
    const kudos = await Kudos.find()
    res.send(kudos)
}

exports.findOne = async (req, res) => {
    const { kudosId } = req.params
    const listKudos = await Kudos.find({ para: kudosId }).sort({ fecha: 'desc' })
    res.send(listKudos)
}

exports.findRabbitKudos = async (id) => {
    console.log(id)
    var listKudos = await Kudos.find({ para: id }).sort({ fecha: 'desc' })
    return listKudos
}

function uidCola() {
    return Math.random().toString() +
        Math.random().toString() +
        Math.random().toString();
}