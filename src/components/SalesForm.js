import { Button, Form, Input, Select } from 'antd';
import { st_sales } from '../endpoints/endpoints';
function SalesForm() {
    const onFinish = (values) => {
        fetch(st_sales, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Basic ' + btoa('masud:1234')
            },
            body: JSON.stringify(values),
        })
            .then((response) => response.json())
            .then((data) => {
                console.log('Success:', data);
            })
            .catch((error) => {
                console.error('Error:', error);
            });
    }
    // const prefixSelector = (
    //     <Form.Item noStyle>
    //         <Select
    //             style={{
    //                 width: 100,
    //             }}
    //             defaultOpen
    //         >
    //             <Select.Option value="880">880</Select.Option>
    //         </Select>
    //     </Form.Item>
    // );

    const onFinishFailed = (errorInfo) => {
        console.log('Failed:', errorInfo);
    };

    return (
        <div>
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
                        // addonBefore={prefixSelector}
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