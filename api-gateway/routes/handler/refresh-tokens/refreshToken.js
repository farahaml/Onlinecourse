const jwt = require('jsonwebtoken');

const apiAdapter = require('../../apiAdapter');

//memanggil sebuah variabel dari config
const {
    URL_SERVICE_USER,
    JWT_SECRET,
    JWT_SECRET_REFRESH_TOKEN,
    JWT_ACCESS_TOKEN_EXPIRED
} = process.env;

//varibael untuk memanggil adapter
const api = apiAdapter(URL_SERVICE_USER);

module.exports = async (req, res) => {
    try {
        const refreshToken = req.body.refresh_token;
        const email = req.body.email;

        //memeriksa apakah email dan refresh token sudah dikirim
        //jika tidak
        if (!refreshToken || !email) {
            return res.status(400).json({
                status: 'error',
                message: 'invalid token'
            });
        };

        //jika diisi, email dan refreshToken akan diperiksa apakah ada di database atau tidak
        await api.get('/refresh_tokens', { params: { refresh_token: refreshToken } });
        
        //jika ada refresh token akan di verifikasi
        jwt.verify(refreshToken, JWT_SECRET_REFRESH_TOKEN, (err, decoded) => {
            if (err) {
                return res.status(403).json({
                    status: 'error',
                    message: err.message });
        };
    
        //mengecek apakah email merupakan email yang sudah terdecode dari refresh token tersebut
        if (email !== decoded.data.email) {
            return res.status(400).json({
                status: 'error',
                message: 'invalid email'
            });
        };

        //jika email sama dengan email yang sudah didecoded
        const token = jwt.sign({ data: decoded.data }, JWT_SECRET, { expiresIn: JWT_ACCESS_TOKEN_EXPIRED });

        //menampilkan token agar daoat digunakan setelah diupdate
        return res.json({
            status:'success',
            data: {
                token
            }
        });
    });
        

    } catch (error) {

        //when service-media off
        if (error.code === 'ECONNREFUSED') {
            return res.status(500).json({ status: 'error', message: 'service unavailable' });
        }

        const { status, data } = error.response;
        return res.status(status).json(data);
    }
}