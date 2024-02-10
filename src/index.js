import App from "./App";
import './scss/index.scss';
import { render } from '@wordpress/element';

// Render the App component into the DOM
render(<App />, document.getElementById('sales-tracker-form'));