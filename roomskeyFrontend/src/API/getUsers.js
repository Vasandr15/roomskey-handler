import axios from "axios";

export const getUsersWithParams = async (roles, name, page = 1) => {
    try {
        const queryParams = new URLSearchParams({
            roles,
            ...(name && { name }),
            page
        });
        console.log(`/api/user?${queryParams}`)
        const response = await axios.get(`/api/user?${queryParams}`, {
            headers: {
                Authorization: `Bearer ${localStorage.getItem('token')}`
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
