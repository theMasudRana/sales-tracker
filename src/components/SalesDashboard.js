import { useEffect, useState } from '@wordpress/element';
import { DatePicker, Spin, Table, notification } from 'antd';
import { st_sales } from '../endpoints/endpoints';
import SalesForm from './SalesForm';
const { RangePicker } = DatePicker;

function SalesDashboard() {

    /**
     * Table columns for the sales dashboard
     * @type {Array}
     */
    const columns = [
        {
            title: 'Buyer',
            dataIndex: 'buyer',
        },
        {
            title: 'Amount',
            dataIndex: 'amount',
        },
        {
            title: 'Receipt ID',
            dataIndex: 'receipt_id',
        },
        {
            title: 'Items',
            dataIndex: 'items',
        },
        {
            title: 'Buyer Email',
            dataIndex: 'buyer_email',
        },
        {
            title: 'Note',
            dataIndex: 'note',
        },
        {
            title: 'City',
            dataIndex: 'city',
        },
        {
            title: 'Phone',
            dataIndex: 'phone',
        },
        {
            title: 'Entry By',
            dataIndex: 'entry_by',
        },
        {
            title: 'Action',
            render: (text, record) => (
                <>
                    <a href="#" data-id={record.id} onClick={editItem}>Edit</a> |
                    <a href="#" data-id={record.id} onClick={deleteItem}> Delete</a>
                </>
            ),
        },
    ];
    const [spinner, setSpinner] = useState(false);
    const [data, setData] = useState([]);
    const [pageNumber, setPageNumber] = useState(1);
    const [perPage, setPerPage] = useState(5);
    const [count, setCount] = useState(0);
    const [currentPage, setCurrentPage] = useState(1);
    const [api, contextHolder] = notification.useNotification();
    const [editMode, setEditMode] = useState(false);
    const [itemID, setItemID] = useState(0);
    const openNotification = (placement, type) => {
        api[type]({
            message: 'Sales item deleted!',
            placement,
            duration: 1,
            onClose: () => fetchData({}),
        });
    };

    // Edit sales item editItem with record id
    const editItem = (e) => {
        e.preventDefault();
        const id = e.target.getAttribute('data-id');
        fetch(`${st_sales}/${id}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': salesTracker.nonce
            },
        })
            .then(response => response.json())
            .then(data => {
                setEditMode(true);
                setItemID(id);
            })
            .catch((error) => {
                console.error('Error:', error);
            });
    }

    // Delete sales item deleteItem with record id
    const deleteItem = (e) => {
        e.preventDefault();
        const id = e.target.getAttribute('data-id');
        fetch(`${st_sales}/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': salesTracker.nonce
            },
        })
            .then(response => response.json())
            .then(data => {
                openNotification('topRight', 'success');
            })
            .catch((error) => {
                console.error('Error:', error);
            });
    }


    const fetchData = async ({ startDate, endDate }) => {
        let getSales = `${st_sales}?per_page=${perPage}&page=${pageNumber}`;
        if (startDate && endDate) {
            getSales = `${st_sales}?per_page=${perPage}&page=${pageNumber}&start_date=${startDate}&end_date=${endDate}`;
        }
        setSpinner(true);
        try {
            const response = await fetch(getSales, {
                headers: {
                    'Content-Type': 'application/json',
                    'X-WP-Nonce': salesTracker.nonce
                },
            });
            const data = await response.json();
            setData(data?.data);
            setCount(data?.total);
            setCurrentPage(data?.current_page);
        } catch (error) {
            console.error("Failed to fetch data: ", error);
        } finally {
            setSpinner(false);
        }
    };

    useEffect(() => {
        fetchData({});
    }, [pageNumber, perPage]);

    const onchange = (date, dateString) => {
        const [startDate, endDate] = dateString;
        fetchData({ startDate, endDate });
    }



    if (spinner) {
        return (
            <div className='spinner-wrapper'>
                <Spin tip="Loading">
                    <div className="content" />
                </Spin>
            </div>
        );
    }

    if (editMode) {
        return (
            <>
                <SalesForm itemID={itemID} editMode={editMode} />
            </>
        );
    }

    return (
        <>
            {contextHolder}
            <div class="st-filter-area" style={{
                marginBottom: "20px"
            }}>
                <h3>Filter by Range</h3>
                <RangePicker onChange={onchange} />
            </div>
            <Table columns={columns} dataSource={data} pagination={{
                total: count,
                current: currentPage,
                defaultPageSize: perPage,
                showSizeChanger: true,
                pageSizeOptions: ["10", "20", "50", "100"],
                onChange: (page_no, perPage) => {
                    setPageNumber(page_no);
                    setPerPage(perPage);
                },
            }} />
        </>
    );
};


export default SalesDashboard