module.exports = (app) => {
    const kudos = require('../controllers/kudos.controller.js');

    app.post('/kudos', kudos.create)
    app.get('/list', kudos.findAll)
    app.get('/kudos/:kudosId', kudos.findOne)
    app.delete('/kudos/:_id', kudos.delete)
}