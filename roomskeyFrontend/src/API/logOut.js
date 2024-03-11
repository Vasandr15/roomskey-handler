import axios from "axios";

export const logOut = async () => {
    try {
        const response = await axios.delete(`/api/user/logout`,{
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