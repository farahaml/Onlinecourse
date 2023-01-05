const {
    User,
    RefreshToken
} = require('../../../models');
const Validator = require('fastest-validator');
const v = new Validator();

module.exports = async(req, res) => {
    const userId = req.body.user_id;
    const refreshToken = req.body.refresh_token;

    //deskripsi skema validasi
    const schema = {
        refresh_token: 'string'
    };

    //memvalidasi
    const validate = v.validate(req.body, schema)
    //jika validasi error akan menampilkan status 400 yang artinya bad parameter
    if (validate.length) {
        return res.status(400).json({
            status: 'error',
            message: validate
        });
    }

    //jika tidak error, program akan memeriksa apakah data userId terdapat di database
    const user = await User.findByPk(userId);
    //jika user tidak ditemukan
    if (!user) {
        return res.status(404).json({
            status: 'error',
            message: 'user not found'
        });
    }

    //ketika validasi sukses dan data userId terdapat di database maka refresh token akan disimpan di database
    const createdRefreshToken = await RefreshToken.create({
        token: refreshToken,
        user_id: userId
    });

    //menampilkan respon sukses
    return res.json({
        status: 'success',
        data : {
            id: createdRefreshToken.id
        }
    });
}