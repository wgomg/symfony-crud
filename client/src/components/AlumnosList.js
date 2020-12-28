import { useEffect, useState } from 'react';
import { useHistory } from 'react-router-dom';
import { useQuery } from 'react-query';

import axios from 'axios';

import { DataTable } from './elements';

import Button from 'react-bootstrap/Button';
import InputGroup from 'react-bootstrap/InputGroup';
import FormControl from 'react-bootstrap/FormControl';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faPlus, faSearch } from '@fortawesome/free-solid-svg-icons';

const AlumnosList = () => {
  const history = useHistory();

  const query = useQuery('alumnos', async () => {
    const baseUrl =
      process.env.NODE_ENV === 'production' ? process.env.PUBLIC_URL : '';

    return (await axios.get(baseUrl + '/api/alumnos')).data;
  });

  const { isError, error, isLoading, isSuccess, data } = query;

  let content = 'Loading...';

  if (!isLoading && isError) {
    const { response } = error;

    if (response.status === 404)
      content = 'The required resource was not found';
    else content = 'Something went wrong, please try again';
  }

  const [alumnos, setAlumnos] = useState([]);

  useEffect(() => {
    if (!isLoading && isSuccess)
      setAlumnos(() =>
        [...data].map((alumno) => {
          const data = {
            ...alumno,
            rut: alumno.rut + '-' + alumno.dv,
            curso:
              (alumno.curso?.grado ?? '') +
              (alumno.curso?.identificador
                ? '-' + alumno.curso.identificador
                : '') +
              (alumno.curso?.nivel ? ' / ' + alumno.curso.nivel.codigo : '')
          };

          delete data.dv;

          return data;
        })
      );
  }, [isLoading, isSuccess, data]);

  content = <DataTable data={alumnos} />;

  const onChange = (e) => {
    if (e.target.value === '')
      setAlumnos(() =>
        [...data].map((alumno) => {
          const data = {
            ...alumno,
            rut: alumno.rut + '-' + alumno.dv,
            curso:
              (alumno.curso?.grado ?? '') +
              (alumno.curso?.identificador
                ? '-' + alumno.curso.identificador
                : '') +
              (alumno.curso?.nivel ? ' / ' + alumno.curso.nivel.codigo : '')
          };

          delete data.dv;

          return data;
        })
      );
    else
      setAlumnos(() =>
        alumnos.filter((alumno) => {
          const matches = Object.values(alumno).map((value) =>
            value
              ?.toString()
              .toUpperCase()
              .includes(e.target.value.toUpperCase())
          );

          return matches.includes(true);
        })
      );
  };

  return (
    <div className="container-fluid w-75">
      <div className="p-3">
        <div className="w-100">
          <Button
            variant="success"
            className="p-1 m-1"
            onClick={() => history.push('/alumnos/nuevo')}
          >
            Nuevo Alumno <FontAwesomeIcon icon={faPlus} />{' '}
          </Button>
          <div className="w-25 float-right">
            <InputGroup>
              <FormControl placeholder="filtrar" onChange={onChange} />
              <InputGroup.Append>
                <InputGroup.Text>
                  <FontAwesomeIcon icon={faSearch} />
                </InputGroup.Text>
              </InputGroup.Append>
            </InputGroup>
          </div>
        </div>
        {content}
      </div>
    </div>
  );
};

export default AlumnosList;
