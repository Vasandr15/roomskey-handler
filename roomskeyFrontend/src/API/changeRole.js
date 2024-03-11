import axios from "axios";

export const changeRole = async (id, role) => {
    try {
        console.log(`/api/user/${id}`)
        console.log(role)
        const response = await axios.patch(`/api/user?id=${id}`, { 'role': role }, {
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
