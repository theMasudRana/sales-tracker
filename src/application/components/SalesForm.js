import { useState } from '@wordpress/element';
import { Button, Form, Input, Select, notification, Row, Col } from 'antd';
import SalesDashboard from './SalesDashboard';
const { Option } = Select;
import { st_sales } from '../endpoints/endpoints';
function SalesForm({ itemID, editMode }) {
    const [form] = Form.useForm();
    const [api, contextHolder] = notification.useNotification();
    const [backToDashboard, setBackToDashboard] = useState(false);
    const openNotification = (placement, type, message) => {
        api[type]({
            message: message,
            placement,
            duration: 1,
        });
    };
    const createItem = (values) => {

        // Disable multiple submission in a day
        // const cookie = document.cookie.split(';').find(c => c.trim().startsWith('sales-tracker-notification='));
        // if (cookie) {
        //     const cookieValue = cookie.split('=')[1];
        //     if (cookieValue === 'true') {
        //         openNotification('topRight', 'error', 'You have already submitted a sales item today!');
        //         return;
        //     }
        // }

        const { prefix, ...rest } = values;
        const newValue = {
            ...rest,
            phone: values.prefix + values.phone,
        }


        fetch(st_sales, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': salesTracker.nonce
            },
            body: JSON.stringify(newValue),
        })
            .then((response) => response.json())
            .then((data) => {
                openNotification('topRight', 'success', 'Sales added successfully!');
                form.resetFields();
                const date = new Date();
                date.setTime(date.getTime() + (24 * 60 * 60 * 1000));
                document.cookie = `sales-tracker-notification=true; expires=${date.toUTCString()}; path=/`;
                console.log('Success:', date);
            })
            .catch((error) => {
                console.error('Error:', error);
            });
    }

    // Prefix selector for phone number
    const prefixSelector = (
        <Form.Item name="prefix" noStyle>
            <Select
                style={{
                    width: 100,
                }}
            >
                <Option value="880">880</Option>
            </Select>
        </Form.Item>
    );

    // Fetch single sales item with record id
    const fetchSalesData = (itemID, form) => {
        if (!itemID || !form) return;

        fetch(`${st_sales}/${itemID}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': salesTracker.nonce
            },
        })
            .then(response => response.json())
            .then(data => {
                form.setFieldsValue(data);
            })
            .catch((error) => {
                console.error('Error:', error);
            });
    }
    fetchSalesData(itemID, form);

    // Update sales item with record id
    const updateItem = (values) => {
        const { prefix, ...rest } = values;
        let phone = values.phone;
        if (!phone.startsWith(prefix)) {
            phone = prefix + phone;
        }
        const newValue = {
            ...rest,
            phone: phone,
        }
        fetch(`${st_sales}/${itemID}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': salesTracker.nonce
            },
            body: JSON.stringify(newValue),
        })
            .then((response) => response.json())
            .then((data) => {
                openNotification('topRight', 'success', 'Sales updated successfully!');
            })
            .catch((error) => {
                console.error('Error:', error);
            });
    }

    // Form submit failed
    const onFinishFailed = (errorInfo) => {
        console.log('Failed:', errorInfo);
    };

    // Go back to SalesDashboard component
    const backButton = () => {
        setBackToDashboard(true);
    }

    return (
        backToDashboard ? <SalesDashboard /> :
            <div>
                {contextHolder}
                <Form
                    name="sales-form"
                    labelCol={{
                        span: 24,
                    }}
                    wrapperCol={{
                        span: 24,
                    }}
                    style={{
                        maxWidth: 1320,
                    }}
                    initialValues={{
                        remember: true,
                        prefix: '880',
                    }}
                    onFinish={editMode ? updateItem : createItem}
                    onFinishFailed={onFinishFailed}
                    autoComplete="off"
                    form={form}
                >
                    <Row gutter={16}>
                        <Col xs={24} sm={24} md={8}>
                            <Form.Item
                                label="Buyer"
                                name="buyer"
                                rows={4}
                                rules={[
                                    {
                                        type: 'string',
                                        required: true,
                                        message: 'Please enter text, spaces, and numbers only, not more than 20 characters.',
                                        max: 20,
                                        pattern: new RegExp(/^[a-zA-Z0-9\s]{1,20}$/),
                                    },
                                ]}
                            >
                                <Input />
                            </Form.Item>
                        </Col>
                        <Col xs={24} sm={24} md={8}>
                            <Form.Item
                                rows={4}
                                label="Amount"
                                name="amount"
                                rules={[
                                    {
                                        required: true,
                                        message: 'Please enter valid number!',
                                        pattern: new RegExp(/^[0-9]+$/),
                                    },
                                ]}
                            >
                                <Input />
                            </Form.Item>
                        </Col>
                        <Col xs={24} sm={24} md={8}>
                            <Form.Item
                                label="Receipt ID"
                                name="receipt_id"
                                rules={[
                                    {
                                        type: 'string',
                                        required: true,
                                        message: 'Please enter only text and spaces.',
                                        pattern: new RegExp(/^[a-zA-Z\s]*$/),
                                    },
                                ]}
                            >
                                <Input />
                            </Form.Item>
                        </Col>
                    </Row>
                    <Row gutter={16}>
                        <Col xs={24} md={12} lg={6}>
                            <Form.Item
                                label="Buyer Email"
                                name="buyer_email"
                                rules={[
                                    {
                                        type: 'string',
                                        required: true,
                                        message: 'Please enter valid email!',
                                        pattern: new RegExp(/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/),
                                    },
                                ]}
                            >
                                <Input />
                            </Form.Item>
                        </Col>
                        <Col xs={24} md={12} lg={6}>
                            <Form.Item
                                name="phone"
                                label="Buyer Phone Number"
                                rules={[
                                    {
                                        required: true,
                                        message: 'Please input your phone number!',

                                    },
                                ]}
                            >
                                <Input
                                    addonBefore={prefixSelector}
                                    style={{
                                        width: '100%',
                                    }}
                                />
                            </Form.Item>
                        </Col>
                        <Col xs={24} md={12} lg={6}>
                            <Form.Item
                                label="City"
                                name="city"
                                rules={[
                                    {
                                        type: 'string',
                                        required: true,
                                        message: 'Please enter only text and spaces.',
                                        pattern: new RegExp(/^[a-zA-Z\s]*$/),
                                    },
                                ]}
                            >
                                <Input />
                            </Form.Item>
                        </Col>
                        <Col xs={24} md={12} lg={6}>
                            <Form.Item
                                label="Entry By"
                                name="entry_by"
                                rules={[
                                    {
                                        required: true,
                                        message: 'Please enter only numbers.',
                                        pattern: new RegExp(/^\d+$/),
                                    },
                                ]}
                            >
                                <Input />
                            </Form.Item>
                        </Col>
                    </Row>
                    <Row gutter={16}>
                        <Col xs={24} sm={24} md={12}>
                            <Form.Item
                                label="Items"
                                name="items"
                                rules={[
                                    {
                                        type: 'string',
                                        required: true,
                                        message: 'Please enter only text and spaces.',
                                        pattern: new RegExp(/^[a-zA-Z\s]*$/),
                                    },
                                ]}
                            >
                                <Input.TextArea />
                            </Form.Item>
                        </Col>
                        <Col xs={24} sm={24} md={12}>
                            <Form.Item
                                label="Note"
                                name="note"
                                rules={[
                                    {
                                        type: 'string',
                                        required: true,
                                        message: 'Please enter not more than 30 words.',
                                        pattern: new RegExp(/^[\s\S]{0,30}$/),
                                    },
                                ]}
                            >
                                <Input.TextArea />
                            </Form.Item>
                        </Col>
                    </Row>
                    <Form.Item
                        wrapperCol={{
                            span: 12,
                        }}
                    >
                        <Button type="primary" htmlType="submit">
                            Submit
                        </Button>
                    </Form.Item>
                </Form>
                {
                    editMode && <Button type="secondary" onClick={backButton}>Back to Dashboard</Button>
                }
            </div>
    )
}

export default SalesForm