import axios from "axios";

export const editUser = async (body) => {
    try {
        const response = await axios.put(`/api/user/profile`, body,
            {
            headers: {
                Authorization: `Bearer ${localStorage.getItem("token")}`
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