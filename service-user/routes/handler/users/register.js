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

    //mencari apakah email
    const user = await User.findOne({
        where: { email: req.body.email }
    });

    //jika email ada
    if (user) {
        //409 berarti data sudah ada dan tidak boleh digunakan lagi
        return res.status(409).json({
            status: 'error',
            message: 'email already exist'
        });
    }

    //apabila email belum terdaftar
    const password = await bcrypt.hash(req.body.password, 10);

    //untuk insert ke database
    const data = {
        password,
        name: req.body.name,
        email: req.body.email,
        profession: req.body.profession,
        role: 'student'
    };
    
    //membuat data
    const createdUser = await User.create(data);

    //penyelesaian
    return res.json({
        status: 'success',
        data: {
            id: createdUser.id
        }
    })
}