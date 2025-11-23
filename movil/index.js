import { registerRootComponent } from 'expo';
import App from './App';

// registerRootComponent llama a AppRegistry.registerComponent('main', () => App);
// También asegura que estés usando el runtime correcto para React Native.
registerRootComponent(App);

