require('dotenv').config();
const express = require('express');
const path = require('path');
const cookieParser = require('cookie-parser');
const logger = require('morgan');

const indexRouter = require('./routes/index');
const usersRouter = require('./routes/users');
const coursesRouter = require('./routes/courses');
const mediaRouter = require('./routes/media');
const paymentsRouter = require('./routes/payments');
const ordersRouter = require('./routes/orders');

const verifyToken = require('./middlewares/verifyToken');


const app = express();

app.use(logger('dev'));
app.use(express.json());
app.use(express.urlencoded({ extended: false }));
app.use(cookieParser());
app.use(express.static(path.join(__dirname, 'public')));

app.use('/', indexRouter);
app.use('/users', usersRouter);
app.use('/courses', verifyToken, coursesRouter);
app.use('/media', mediaRouter);
app.use('/payments', paymentsRouter);
app.use('/orders', ordersRouter);


module.exports = app;
