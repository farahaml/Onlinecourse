/*upload data*/

const apiAdapter = require('../../apiAdapter');

const jwt =require('jsonwebtoken');


//memanggil sebuah variabel dari config
const {
    URL_SERVICE_USER,
    JWT_SECRET,
    JWT_SECRET_REFRESH_TOKEN,
    JWT_ACCESS_TOKEN_EXPIRED,
    JWT_REFRESH_TOKEN_EXPIRED
} = process.env;

//varibael untuk memanggil adapter
const api = apiAdapter(URL_SERVICE_USER);

//fungsi untuk memanggil token
module.exports = async (req, res) => {
    try {
      const user = await api.post('/users/login', req.body);
      const data = user.data.data;

      //generate toke
      const token = jwt.sign({ data }, JWT_SECRET, { expiresIn: JWT_ACCESS_TOKEN_EXPIRED });
    //generate refresh token
    const refreshToken = jwt.sign({ data }, JWT_SECRET_REFRESH_TOKEN, { expiresIn: JWT_REFRESH_TOKEN_EXPIRED });

        //menyimpan refresh token pada table refresh-tokens
    await api.post('/refresh_tokens', { refresh_token: refreshToken, user_id: data.id });


    return res.json({
        status: 'success',
        data: {
            user:  data,
            token,
            refresh_token: refreshToken
        }
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