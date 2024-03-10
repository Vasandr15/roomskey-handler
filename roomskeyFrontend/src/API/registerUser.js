import axios from "axios";
export const registerUser = (data) => {
    axios.post(`user/register`, data)
        .then(res => {
            console.log('Status:', res.status);
            console.log('Data:', res.data);
            return  res.status
        })
        .catch(error => {
            console.error('An error occurred:', error.response ? error.response.status : error.message);
            return error.response.status;
        });
}
