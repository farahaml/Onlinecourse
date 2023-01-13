const jwt = require('jsonwebtoken');
const { JWT_SECRET } = process.env;

//middleware
module.exports = (req, res, next) => {
    const token = req.headers.authorization;
    
    //setelah mendapat token dari header, token akan divalidasi
    jwt.verify(token, JWT_SECRET, function(err, decoded) {
    if (err) {
        return res.status(403).json({ message: err.message });
        }

    //jika tidak ada error maka data decoded akan diinjectkan pada object request
    //data dapat digunakan di api gateway selama menggunakan middleware
    req.user = decoded;
    return next();
    });
    }
