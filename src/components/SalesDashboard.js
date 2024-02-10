import { useEffect, useState } from '@wordpress/element';
import { DatePicker, Spin, Table } from 'antd';
import { st_sales } from '../endpoints/endpoints';
const { RangePicker } = DatePicker;
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
                <a href={`/wp-admin/post.php?post=${record.id}&action=edit`}>Edit</a> |
                <a href={`/wp-admin/post.php?post=${record.id}&action=edit`}> Delete</a>
            </>
        ),
    },
];
function SalesDashboard() {
    const [spinner, setSpinner] = useState(false);
    const [data, setData] = useState([]);
    const [pageNumber, setPageNumber] = useState(1);
    const [perPage, setPerPage] = useState(5);
    const [count, setCount] = useState(0);
    const [currentPage, setCurrentPage] = useState(1);

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

    return (
        <>
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