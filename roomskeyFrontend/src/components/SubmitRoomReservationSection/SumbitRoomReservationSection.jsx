import ReservationCard from "../RequestCards/ReservationRequest/ReservationRequestCard.jsx";
import { useState, useEffect } from 'react';
import {getAllBids} from "../../API/getAllBids.js";
import {Flex, Pagination} from "antd";

export default function SubmitReservationSection() {
    const [bids, setBids] = useState([]);
    const [pagination, setPagination] = useState({});

    useEffect(() => {
        const fetchData = async () => {
            try {
                const data = await getAllBids();
                if (data) {
                    setBids(data.bids);
                    setPagination(data.pagination);
                }
            } catch (error) {
                console.error('Error fetching data:', error);
            }
        };

        fetchData();
    }, []);

    const updateBids = async () => {
        try {
            const data = await getAllBids();
            if (data) {
                setBids(data.bids);
                setPagination(data.pagination);
            }
        } catch (error) {
            console.error('Error updating bids:', error);
        }
    };

    return (
        <Flex vertical align="center" justify="center">
            <Flex vertical align="center" justify="center">
                {bids && bids.map(bid => (
                    <ReservationCard key={bid.keyId} data={bid} onUpdate={updateBids} />
                ))}
            </Flex>
            <Pagination defaultCurrent={1} total={50} style={{ marginTop: '30px', marginBottom: '30px' }} />
        </Flex>
    );
}