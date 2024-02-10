import { Button, Form, Input, Select, notification } from 'antd';
const { Option } = Select;
import { st_sales } from '../endpoints/endpoints';
function SalesForm() {
    const [form] = Form.useForm();
    const [api, contextHolder] = notification.useNotification();
    const openNotification = (placement) => {
        api.info({
            message: 'Sales added successfully!',
            placement,
        });
    };
    const onFinish = (values) => {

        // Check if the sales-tracker-notification cookie is expired or not
        const cookie = document.cookie.split(';').find(c => c.trim().startsWith('sales-tracker-notification='));
        if (cookie) {
            const cookieValue = cookie.split('=')[1];
            if (cookieValue === 'true') {
                openNotification('topRight', 'You have already added a sale today!');
                // alert('You have already added a sale today!');
                // return;
            }
        }

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
                openNotification('topRight', 'Sales added successfully!');
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
    const prefixSelector = (
        <Form.Item name="prefix" noStyle>
            <Select
                style={{
                    width: 100,
                }}
            >
                <Option value="880">+880</Option>
            </Select>
        </Form.Item>
    );

    const onFinishFailed = (errorInfo) => {
        console.log('Failed:', errorInfo);
    };

    return (
        <div>
            {contextHolder}
            <Form
                name="sales-form"
                labelCol={{
                    span: 24,
                }}
                wrapperCol={{
                    span: 16,
                }}
                style={{
                    maxWidth: 800,
                }}
                initialValues={{
                    remember: true,
                    prefix: '880',
                }}
                onFinish={onFinish}
                onFinishFailed={onFinishFailed}
                autoComplete="off"
                form={form}
            >
                <Form.Item
                    label="Buyer"
                    name="buyer"
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
                <Form.Item
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
        </div>
    )
}

export default SalesForm