import axios from "axios";

export const changeStatus = async (id, body, token) => {
    try {
        const response = await axios.patch(`/api/bid?id=${id}`, body, {
            headers: {
                Authorization: `Bearer de329f6f-78cd-455f-b530-1b5216218935`
            }
        });
        console.log('Status:', response.status);
        console.log('Data:', response.data);
        return response.data;
    } catch (error) {
        console.error('An error occurred:', error.response ? error.response.status : error.message);
        return null;
    }
};