/*show mentor detail*/

const apiAdapter = require('../../apiAdapter');

//memanggil sebuah variabel dari config
const {
    URL_SERVICE_COURSE
} = process.env;

//varibael untuk memanggil adapter
const api = apiAdapter(URL_SERVICE_COURSE);

module.exports = async (req, res) => {
    try {
        const id = req.params.id;
        const mentors = await api.get(`/api/mentors/${id}`);
      //reponse if success
      return res.json(mentors.data);  
    } catch (error) {

        //when service-media off
        if (error.code === 'ECONNREFUSED') {
            return res.status(500).json({ status: 'error', message: 'service unavailable' });
        }

        const { status, data } = error.response;
        return res.status(status).json(data);
    }
}