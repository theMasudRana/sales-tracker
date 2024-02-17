import SalesForm from "./components/SalesForm";
import SalesDashboard from "./components/SalesDashboard";
import './scss/index.scss';
import { render } from '@wordpress/element';

const salesTrackerForm = document.getElementById('sales-tracker-form');
const salesTrackerDashboard = document.getElementById('sales-tracker-dashboard');

// Render the App component into the DOM
if (salesTrackerForm) {
    render(<SalesForm />, salesTrackerForm);
}
if (salesTrackerDashboard) {
    render(<SalesDashboard />, salesTrackerDashboard);
}
