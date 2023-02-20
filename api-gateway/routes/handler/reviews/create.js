/* creating My Course data */

const apiAdapter = require('../../apiAdapter');

//call config variable
const {
    URL_SERVICE_COURSE
} = process.env

//call adapter
const api = apiAdapter(URL_SERVICE_COURSE);

module.exports = async (req, res) => {
    try {
        const userId = req.user.data.id;
        const review = await api.post('/api/reviews', {
                user_id: userId,
                ...req.body
        });

        //success response 
        return res.json(review.data);
    } catch (error) {
        //if service off
        if (error.message === 'ECONNREFUSED') {
            //error response
            return res.status(500).json({
                status: 'error',
                message: 'service unavailable'
            });
        }

        const { status, data } = error.response;

        return res.status(status).json(data);
    }
}