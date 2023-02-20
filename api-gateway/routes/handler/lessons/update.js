/* updating lesson data by id*/

const apiAdapter = require('../../apiAdapter');

//call config variable
const {
    URL_SERVICE_COURSE
} = process.env;

//calling api adapter
const api = apiAdapter(URL_SERVICE_COURSE);

module.exports = async (req, res) => {
    try {
        //req id from database
        const id = req.params.id;
        //req lesson data
        const lesson = await api.put(`/api/lessons/${id}`, req.body);

        //success response
        return res.json(lesson.data);
    } catch (error) {
        // if service off
        if (error.code === 'ECONNREFUSED') {
            return res.status(500).json({
                status: 'error',
                message: 'service unavailable'
            });
        }
        const { status, data } = error.response;
        //error response
        return res.status(status).json(data);
    }
}