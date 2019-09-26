const express = require('express')
const bodyParser = require('body-parser')
const mongoose = require('mongoose')
const morgan = require('morgan')

const configDB = require('./config/database.config.js')

// INIT
const app = express()

// DATABASE
mongoose.connect(configDB.url, {
    useNewUrlParser: true,
    useUnifiedTopology: true
}).then(db => console.log('Se conecto con MONGO XD!!')).catch(err => console.error(err));

// SETTINGS
app.set('port', process.env.PORT || 7001);
mongoose.Promise = global.Promise;

// MIDDLEWARE
app.use(bodyParser.json())
app.use(morgan('dev'));
app.use(bodyParser.urlencoded({ extended: true }))

// ROUTES
require('./app/routes/kudos.routes.js')(app)
require('./app/routes/stats.routes.js')(app)

// INIT SERVER 
app.listen(app.get('port'), () => {
    console.log('It WORKS ON PORT ', app.get('port'));
})