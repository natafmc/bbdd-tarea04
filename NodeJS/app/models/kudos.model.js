const mongoose = require('mongoose');

const KudosSchema = mongoose.Schema({
    de: { type: Number, required: true },
    nombreDe: { type: String, required: true },
    para: { type: Number, required: true },
    nombrePara: { type: String, required: true },
    tema: { type: String, required: true },
    fecha: { type: Date, default: Date.now },
    lugar: { type: String },
    texto: { type: String, required: true },
});

module.exports = mongoose.model('Kudos', KudosSchema);