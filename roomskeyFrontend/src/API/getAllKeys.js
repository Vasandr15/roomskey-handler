import axios from "axios";

export const getAllKeys = async (filters) => {
    try {
        const { date, buildings, rooms, inStock } = filters;

        let url = '/api/getAllKeys';

        const params = new URLSearchParams();
        if (date) {
            params.append('date', date.toISOString());
        }
        if (buildings) {
            buildings.forEach((building) => params.append('building', building));
        }
        if (rooms) {
            rooms.forEach((room) => params.append('room', room));
        }

        let response;
        if (inStock) {
            url = '/api/getMyKeys';
            const token = localStorage.getItem('token');
            if (!token) {
                console.error('Token not found in localStorage');
                return null;
            }
            //поменять токен
            console.log("запрос сделан")
            response = await axios.get(url, { params, headers: { Authorization: `Bearer ${localStorage.getItem('token')}` } }); //token
        } else {
            response = await axios.get(url, { params });
        }

        console.log('Status:', response.status);
        console.log('Data:', response.data);
        return response.data;
    } catch (error) {
        console.error('An error occurred:', error.response ? error.response.status : error.message);
        return null;
    }
};