import axios from "axios";

export const getUsers = async () => {
    try {
        const response = await axios.get(`/api/user`);
        console.log('Status:', response.status);
        console.log('Data:', response.data);
        return response.data;
    } catch (error) {
        console.error('An error occurred:', error.response ? error.response.status : error.message);
        return null;
    }
}