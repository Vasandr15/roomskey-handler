import axios from "axios";

export const registerUser = async (data) => {
    try {
        const response = await axios.post(`api/user/register`, data);
        console.log('Status:', response.status);
        console.log('Data:', response.data);
        return response.data.token; // Return the token from the response data
    } catch (error) {
        console.error('An error occurred:', error.response ? error.response.status : error.message);
        return null;
    }
}
