/*guna mencari user berdasarkan id apabila datanya dibutuhkan*/

const { User } = require('../../../models');

module.exports = async (req, res) => {
    //mengambil data id dari parameter
    const id = req.params.id;

    const user = await User.findByPk(id);
    
    //respon error jika user tidak ditemukan
    if(!user) {
        return res.status(404).json({
            status:'error',
            message: 'user not found'
        });
    }

    //respon jika user ditemukan
    return res.json({
        status: 'success',
        data: user
    });
}