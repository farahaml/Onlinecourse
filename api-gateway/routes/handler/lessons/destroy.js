/* delete lesson data by id */

const apiAdapter = require('../../apiAdapter');

//call config variable
const {
    URL_SERVICE_COURSE
} = process.env;

//call adapter
const api = apiAdapter(URL_SERVICE_COURSE);

module.exports = async (req, res) => {
    try {
        //req lesson id
        const id = req.params.id;
        //req lesson data by id
        const lesson = await api.delete(`/api/lessons/${id}`);

        //success response
        return res.json(lesson.data);
    } catch (error) {
        //if service off
        if (error.code === 'ECONNREFUSED') {
            return res.status(500).json({
                status: 'error',
                message: 'service unavailable'
            });
        }

        const { status, data } = error.response;
        return res.status(status).json(data);
    }
}