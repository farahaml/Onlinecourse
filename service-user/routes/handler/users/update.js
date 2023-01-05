/*Untuk mengupdate profile*/

const bcrypt = require('bcrypt');
const { User } = require('../../../models');
const Validator = require('fastest-validator');
const v = new Validator();

module.exports = async(req, res) => {
    //skema validasi
    const schema = {
        name: 'string|empty:false',
        email: 'email|empty:false',
        password: 'string|min:6',
        profession: 'string|optional',
        avatar: 'string|optional'
    };

    //jika error
    const validate = v.validate(req.body, schema);
    if(validate.length) {
        return res.status(400).json({
            status: 'error',
            message: validate
        });
    }

    //jika tidak error, mencari user yang ingin diupdate pada databsase
    const id = req.params.id;
    const user = await User.findByPk(id);
    if(!user) {
        return res.status(404).json({
            status: 'error',
            message: 'user not found'
        });
    }

    //jika user ada, program akan mencari email yang dimasukkan pada database
    const email = req.body.email;
    if(email) {
        const checkEmail = await User.findOne({
            where: { email }
        });

        //error jika email yang dimasukkan sama dengan email yang sebelumnya
        if (checkEmail && email !== user.email) {
            return res.status(409).json({
                status: 'error',
                message: 'email already exist'
            })
        }
    }

    const password = await bcrypt.hash(req.body.password, 10);
    //mengambil sebuah variabel dari body
    const {
        name, profession, avatar
    } = req.body;

    //ketika validasi sukses, maka profile akan diupdate
    await user.update({
        email,
        password,
        name,
        profession,
        avatar
    });

    //respon sukses ketika tidak ada error
    return res.json({
        status: 'succes',
        data: {
            id:user.id,
            name,
            email,
            profession,
            avatar
        }
    });
}