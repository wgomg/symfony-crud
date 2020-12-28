import { useEffect, useState } from 'react';
import { useHistory } from 'react-router-dom';
import { useMutation, useQueryClient } from 'react-query';

import axios from 'axios';

import Table from 'react-bootstrap/Table';
import Button from 'react-bootstrap/Button';
import ButtonGroup from 'react-bootstrap/ButtonGroup';
import Card from 'react-bootstrap/Card';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import {
  faSortUp,
  faSortDown,
  faTrash,
  faEdit,
  faSkullCrossbones
} from '@fortawesome/free-solid-svg-icons';

import { confirmAlert } from 'react-confirm-alert';
import 'react-confirm-alert/src/react-confirm-alert.css';

const DataTable = ({ data }) => {
  const history = useHistory();

  const [sorting, setSorting] = useState({});
  const [sortColumn, setSortColumn] = useState('');
  const [tableData, setTableData] = useState([]);

  useEffect(() => {
    setTableData([...data]);
  }, [data]);

  useEffect(() => {
    setSorting(() =>
      Object.fromEntries(
        Object.entries(data[0] ?? {}).map(([key, val]) => [key, false])
      )
    );
  }, [setSorting, data]);

  const sortColum = (columnSort) => {
    setSorting(() => ({ ...sorting, [columnSort]: !sorting[columnSort] }));
    setSortColumn(columnSort);

    const sortOrder = sorting[columnSort] ? 1 : -1;

    setTableData(() =>
      tableData.sort((a1, a2) => {
        const col1 = a1[columnSort];
        const col2 = a2[columnSort];

        if (col1 < col2) return sortOrder;
        if (col1 > col2) return -sortOrder;
        return 0;
      })
    );
  };

  const qc = useQueryClient();
  const mutation = useMutation((id) => axios.delete('/api/alumnos/' + id), {
    onSuccess: () => qc.invalidateQueries('alumnos')
  });
  const { isLoading, mutate, error } = mutation;

  useEffect(() => {
    if (!isLoading && error)
      alert('Algo salió mal, por favor intenta nuevamente');
  }, [isLoading, error]);

  const onDelete = (alumno) => {
    confirmAlert({
      customUI: ({ onClose }) => (
        <Card
          border="danger"
          className="bg-dark"
          style={{ width: '30rem' }}
          body
        >
          <Card.Title>
            <FontAwesomeIcon icon={faSkullCrossbones} className="text-danger" />{' '}
            Confirme para borrar
          </Card.Title>
          <Card.Text>{`${alumno.rut} ${alumno.nombre} ${alumno.ap_paterno} ${alumno.ap_materno}`}</Card.Text>
          <ButtonGroup className="btn-block">
            <Button
              variant="danger w-50 mr-2 ml-2"
              onClick={() => {
                mutate(alumno.id);
                onClose();
              }}
            >
              Borrar
            </Button>
            <Button variant="secondary w-50 mr-2 ml-2" onClick={onClose}>
              Cancelar
            </Button>
          </ButtonGroup>
        </Card>
      )
    });
  };

  const columns = [
    { text: '#', name: 'id' },
    { text: 'Nombres', name: 'nombre' },
    { text: 'Paterno', name: 'ap_paterno' },
    { text: 'Materno', name: 'ap_paterno' },
    { text: 'RUT', name: 'rut' },
    { text: 'Dirección', name: 'direccion' },
    { text: 'Celular', name: 'celular' },
    { text: 'Facebook', name: 'facebook' },
    { text: 'Instagram', name: 'instagram' },
    { text: 'Curso', name: 'curso' }
  ];

  return (
    <Table responsive striped bordered hover size="sm" variant="dark">
      <thead>
        <tr>
          {columns.map((column, index) => (
            <th
              key={index}
              onClick={() => sortColum(column.name)}
              style={{ cursor: 'pointer' }}
            >
              <div className={column.name}>
                <FontAwesomeIcon
                  icon={sorting[column.namenName] ? faSortUp : faSortDown}
                  className="m-1"
                  color={sortColumn === column.name ? '#1eff00' : ''}
                />{' '}
                {column.text}
              </div>
            </th>
          ))}
          <th></th>
        </tr>
      </thead>
      <tbody>
        {tableData.map((alumno, index) => (
          <tr key={index}>
            {Object.entries(alumno).map(([col, value], index) => (
              <td key={index} className={col}>
                {value}
              </td>
            ))}
            <td>
              <ButtonGroup className="btn-block" size="sm">
                <Button
                  variant="info"
                  type="button"
                  onClick={() =>
                    history.push('/alumnos/' + alumno.rut + '/editar')
                  }
                >
                  <FontAwesomeIcon icon={faEdit} />
                </Button>
                <Button
                  variant="danger"
                  type="button"
                  onClick={() => onDelete(alumno)}
                >
                  <FontAwesomeIcon icon={faTrash} />
                </Button>
              </ButtonGroup>
            </td>
          </tr>
        ))}
      </tbody>
    </Table>
  );
};

export default DataTable;
