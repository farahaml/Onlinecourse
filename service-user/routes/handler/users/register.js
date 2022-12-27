/*menghandle request registrasi pada web*/


//library bcrypt
const bcrypt = require('bcrypt');
//memanggil model User
const { User } = require('../../../models');
//validator data
const Validator = require('fastest-validator');
//mendeklarasi validator
const v = new Validator();

module.exports = async (req, res) => {
    const schema = {
        name: 'string|empty:false',
        email: 'email|empty:false',
        password: 'string|min:6',
        profession: 'string|optional'
    }

    const validate = v.validate(req.body, schema);

    if (validate.length) {
        return res.status(400).json({
            status: 'error',
            message: validate
        });
    }

    //mencari apakah email sudah ada di table users atau belum
    const user = await User.findOne({
        where: { email: req.body.email }
    });

    //jika email ada
    if (user) {
        //409 berarti data sudah ada dan tidak boleh digunakan lagi
        return res.status(409).json({
            status: 'error',
            message: 'email already exists'
        });
    }
}