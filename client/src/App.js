import { BrowserRouter as Router, Route, Switch } from 'react-router-dom';

import { AlumnosList, EditAlumno, NewAlumno } from './components';

import 'bootstrap/dist/css/bootstrap.min.css';
import './App.css';

function App() {
  const baseUrl =
    process.env.NODE_ENV === 'production' ? process.env.PUBLIC_URL : '';

  return (
    <Router basename={baseUrl}>
      <Switch>
        <Route exact path="/" component={AlumnosList} />
        <Route path="/alumnos/nuevo" component={NewAlumno} />
        <Route path="/alumnos/:rut/editar" component={EditAlumno} />
      </Switch>
    </Router>
  );
}

export default App;
