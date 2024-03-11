import axios from "axios";

export const giveKey = async (nextKeeperId) => {
    try {
        const token = localStorage.getItem("token");
        if (!token) {
            console.error('Token not found in localStorage');
            return null;
        }

        const response = await axios.post(
            '/api/giveKey',
            { nextKeeperId },
            {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            }
        );

        console.log('Status:', response.status);
        console.log('Data:', response.data);
        return response.data;
    } catch (error) {
        console.error('An error occurred:', error.response ? error.response.status : error.message);
        return null;
    }
}