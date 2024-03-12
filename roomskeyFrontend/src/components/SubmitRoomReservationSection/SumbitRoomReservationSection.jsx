import ReservationCard from "../RequestCards/ReservationRequest/ReservationRequestCard.jsx";
import React, { useState, useEffect } from 'react';
import {getAllBids} from "../../API/getAllBids.js";
import {Flex, Pagination} from "antd";
import styles from "../userList/styles.module.css";

export default function SubmitReservationSection() {
    const [current, setCurrent] = useState(1);
    const [bids, setBids] = useState([]);
    const [total, setTotal] = useState(0);

    useEffect(() => {
        fetchData();
    }, [current]);

    const onChange = (page) => {
        setCurrent(page);
    }

    const fetchData = async () => {
        try {
            const data = await getAllBids(current);
            if (data) {
                setBids(data.bids);
                setTotal(data.pagination ? data.pagination.size * data.pagination.count : 0);
            }
        } catch (error) {
            console.error('Error fetching data:', error);
        }
    };

    const updateBids = async () => {
        try {
            const data = await getAllBids(current);
            if (data) {
                setBids(data.bids);
                setPagination(data.pagination);
            }
        } catch (error) {
            console.error('Error updating bids:', error);
        }
    };

    const awaitingConfirmationBids = bids.filter(bid => bid.status === 'awaiting confirmation');

    return (
        <Flex vertical align="center" justify="center">
            <Flex vertical align="center" justify="center">
                {awaitingConfirmationBids && awaitingConfirmationBids.map(bid => (
                    <ReservationCard key={bid.keyId} data={bid} onUpdate={updateBids} />
                ))}
            </Flex>

            {total > 10 ? (
                <Pagination current={current} onChange={onChange} total={total} style={{ marginTop: '30px', marginBottom: '30px' }} />
            ) : null}
        </Flex>
    );
}