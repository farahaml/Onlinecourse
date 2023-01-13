/*upload data*/

const apiAdapter = require('../../apiAdapter');

//memanggil sebuah variabel dari config
const {
    URL_SERVICE_USER,
    JWT_SECRET,
    JWT_SECRET_REFRESH_TOKEN,
    JWT_ACCESS_TOKEN_EXPIRED
} = process.env;

//varibael untuk memanggil adpter
const api = apiAdapter(URL_SERVICE_USER);

module.exports = async (req, res) => {
    try {
    //meminta refresh token dan email
    const refreshToken = req.body.refresh_token;
    const email = req.body.email;

    //mengecek apakah refresh token dan email dikirim oleh user
    //jika tidak maka akan menampilkan error invalid
    if (!refreshToken ||!email) {
        return res.status(400).json({
            status: 'error',
            error: 'invalid token'
        });
    };

    //jika diisi, maka akan diperiksa apakah ada pada database atau tidak
    //memanggil api dari service-user untuk memeriksa database
    await api.gets('/refresh_tokens', { params: { refresh_token: refreshToken } });

    //setelah ditemukan di database, refresh token akan diperiksa apakah sudah expired atau belum
    jwt.verify(refreshToken, JWT_SECRET_REFRESH_TOKEN, (err, decoded) => {
        if (err) {
            return res.status(403).json({
                    status: 'error',
                    error: err.message});
            }
                //memeriksa apakah email yang dimasukan sesuai dengan yang sudah didecode pada token
    if (email!== decoded.email) {
        return res.status(400).json({
            status: 'error',
            error: 'email is not valid'
        });
    }

    //jika email sesuai
    const token = jwt.sign({ data: decoded.data }, JWT_SECRET, { expiresIn: JWT_ACCESS_TOKEN_EXPIRED });

    //menampilkan data agar token dapat digunakan
    return res.json({
        status:'success',
        data: {
            token
        }
        });
    });
}
    //mengecek apakah refresh token sesuai dengan email
  catch (error) {
        //when service-user off
        if (error.code === 'ECONNREFUSED') {
            return res.status(500).json({ status: 'error', message: 'service unavailable' });
  }
        const { data } = error.response;
        return res.status(404).json(data);
    }
}