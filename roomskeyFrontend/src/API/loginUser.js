import axios from "axios";

export const loginUser = async (data) => {
    try {
        const response = await axios.post(`api/user/login`, data);
        return response.data.token;
    } catch (error) {
        return null;
    }
}