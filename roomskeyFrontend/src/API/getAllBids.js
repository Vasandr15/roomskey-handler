import axios from "axios";

export const getAllBids = async (page = 1) => {
    try {
        const response = await axios.get(`/api/bid?page=${page}`, {
            headers: {
                Authorization: `Bearer ${localStorage.getItem('token')}` // token
            }
        });
        console.log('Status:', response.status);
        console.log('Data:', response.data);
        return response.data;
    } catch (error) {
        console.error('An error occurred:', error.response ? error.response.status : error.message);
        return null;
    }
}