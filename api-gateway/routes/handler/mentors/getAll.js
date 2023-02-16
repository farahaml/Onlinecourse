/*show all data*/

const apiAdapter = require('../../apiAdapter');

//memanggil sebuah variabel dari config
const {
    URL_SERVICE_COURSE
} = process.env;

//varibael untuk memanggil adapter
const api = apiAdapter(URL_SERVICE_COURSE);

module.exports = async (req, res) => {
    try {
      const mentors = await api.get('/api/mentors');
      //reponse if success
      return res.json(mentors.data);  
    } catch (err) {
        //when service-course off
        if (error.code === 'ECONNREFUSED') {
            return res.status(500).json({ status: 'error', message: 'service unavailable' });
        }

        const { status, data } = error.code;
        return res.status(status).json(data);
    }
}