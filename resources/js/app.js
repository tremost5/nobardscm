import './bootstrap';

import Alpine from 'alpinejs';
import { registrationForm } from './alpine/registration-form';

window.Alpine = Alpine;
window.registrationForm = registrationForm;

Alpine.start();