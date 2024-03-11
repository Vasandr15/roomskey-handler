import axios from "axios";

export const getAllBids = async () => {
    try {
        const response = await axios.get(`/api/bid`, {
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
}