import { useEffect, useState } from 'react';
import { useHistory } from 'react-router-dom';
import { useQuery, useMutation } from 'react-query';

import axios from 'axios';

import Form from 'react-bootstrap/Form';
import Col from 'react-bootstrap/Col';
import Button from 'react-bootstrap/Button';
import ButtonGroup from 'react-bootstrap/ButtonGroup';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faArrowLeft } from '@fortawesome/free-solid-svg-icons';

const NewAlumno = () => {
  const query = useQuery('cursos', async () => {
    const baseUrl =
      process.env.NODE_ENV === 'production' ? process.env.PUBLIC_URL : '';

    return (await axios.get(baseUrl + '/api/cursos')).data;
  });
  const { isError, error, isLoading, isSuccess, data } = query;

  let content = 'Loading...';

  if (!isLoading && isError) {
    const { response } = error;

    if (response.status === 404)
      content = 'The required resource was not found';
    else content = 'Something went wrong, please try again';
  }

  const [cursos, setCursos] = useState([]);

  useEffect(() => {
    if (!isLoading && isSuccess)
      setCursos(() =>
        [...data].map((curso) => ({
          id: curso.id,
          text:
            curso.grado +
            (curso.identificador ?? '') +
            ' - ' +
            (curso.nivel?.codigo ?? '')
        }))
      );
  }, [isLoading, isSuccess, data]);

  const cursosOptions = cursos.map((curso, index) => (
    <option value={curso.id} key={index}>
      {curso.text}
    </option>
  ));

  const mutation = useMutation(async (newAlumno) => {
    const baseUrl =
      process.env.NODE_ENV === 'production' ? process.env.PUBLIC_URL : '';

    await axios.post(baseUrl + '/api/alumnos', newAlumno);
  });
  const {
    isLoading: isSending,
    mutate,
    error: postError,
    isSuccess: postSuccess
  } = mutation;

  const onSubmit = (e) => {
    e.preventDefault();

    setFormErrors({});

    const inputs = Array.from(e.target);
    const newAlumno = Object.fromEntries(
      inputs.map((input) => [input.id, input.value])
    );

    mutate(newAlumno);
  };

  const [formErrors, setFormErrors] = useState({});

  useEffect(() => {
    if (!isSending && postError) setFormErrors(postError.response.data);
  }, [isSending, postError]);

  const history = useHistory();
  useEffect(() => {
    if (postSuccess) history.push('/');
  }, [postSuccess, history]);

  content = (
    <Form onSubmit={onSubmit}>
      <Form.Row>
        <Form.Group as={Col} xs={2} controlId="rut">
          <Form.Label>RUT (*)</Form.Label>
          <Form.Control
            type="text"
            minLength="8"
            maxLength="8"
            pattern="[0-9]+?"
            required
            isInvalid={formErrors.rut}
          />

          <Form.Control.Feedback type="invalid">
            {formErrors.rut}
          </Form.Control.Feedback>
        </Form.Group>

        <Form.Group as={Col} xs={1} controlId="dv">
          <Form.Label>dv (*)</Form.Label>
          <Form.Control
            type="text"
            maxLength="1"
            pattern="[0-9kK]"
            required
            isInvalid={formErrors.dv}
          />

          <Form.Control.Feedback type="invalid">
            {formErrors.dv}
          </Form.Control.Feedback>
        </Form.Group>

        <Form.Group as={Col} controlId="curso_id">
          <Form.Label>Curso (*)</Form.Label>
          <Form.Control as="select" required isInvalid={formErrors.curso}>
            <option value="">Seleccionar...</option>
            {cursosOptions}
          </Form.Control>

          <Form.Control.Feedback type="invalid">
            {formErrors.curso}
          </Form.Control.Feedback>
        </Form.Group>
      </Form.Row>

      <Form.Row>
        <Form.Group as={Col} controlId="nombre">
          <Form.Label>Nombres (*)</Form.Label>
          <Form.Control
            type="text"
            maxLength="45"
            required
            isInvalid={formErrors.nombre}
          />

          <Form.Control.Feedback type="invalid">
            {formErrors.nombre}
          </Form.Control.Feedback>
        </Form.Group>
      </Form.Row>

      <Form.Row>
        <Form.Group as={Col} controlId="ap_paterno">
          <Form.Label>Apellido Paterno (*)</Form.Label>
          <Form.Control
            maxLength="45"
            required
            isInvalid={formErrors.ap_paterno}
          />

          <Form.Control.Feedback type="invalid">
            {formErrors.ap_paterno}
          </Form.Control.Feedback>
        </Form.Group>

        <Form.Group as={Col} controlId="ap_materno">
          <Form.Label>Apellido Materno (*)</Form.Label>
          <Form.Control
            maxLength="45"
            required
            isInvalid={formErrors.ap_materno}
          />

          <Form.Control.Feedback type="invalid">
            {formErrors.ap_materno}
          </Form.Control.Feedback>
        </Form.Group>
      </Form.Row>

      <Form.Row>
        <Form.Group as={Col} controlId="direccion">
          <Form.Label>Dirección (*)</Form.Label>
          <Form.Control
            maxLength="255"
            required
            isInvalid={formErrors.direccion}
          />

          <Form.Control.Feedback type="invalid">
            {formErrors.direccion}
          </Form.Control.Feedback>
        </Form.Group>

        <Form.Group as={Col} xs={3} controlId="celular">
          <Form.Label>Celular (*)</Form.Label>
          <Form.Control
            minLength="9"
            maxLength="9"
            pattern="[0-9]+?"
            required
            isInvalid={formErrors.celular}
          />

          <Form.Control.Feedback type="invalid">
            {formErrors.celular}
          </Form.Control.Feedback>
        </Form.Group>
      </Form.Row>

      <hr className="border border-secondary" />

      <Form.Row>
        <Form.Group as={Col} controlId="facebook">
          <Form.Label>Facebook</Form.Label>
          <Form.Control type="url" isInvalid={formErrors.facebook} />

          <Form.Control.Feedback type="invalid">
            {formErrors.facebook}
          </Form.Control.Feedback>
        </Form.Group>

        <Form.Group as={Col} controlId="instagram">
          <Form.Label>Instagram</Form.Label>
          <Form.Control type="url" isInvalid={formErrors.instagram} />

          <Form.Control.Feedback type="invalid">
            {formErrors.instagram}
          </Form.Control.Feedback>
        </Form.Group>
      </Form.Row>

      <hr className="border border-secondary" />

      <ButtonGroup className="btn-block">
        <Button
          variant="secondary"
          as={Col}
          xs={1}
          type="button"
          onClick={() => history.push('/')}
        >
          <FontAwesomeIcon icon={faArrowLeft} />
        </Button>
        <Button variant="primary" type="submit">
          Guardar
        </Button>
      </ButtonGroup>
    </Form>
  );

  return (
    <div className="container-fluid w-50">
      <div className="p-3">
        <h4>
          <strong>Nuevo Alumno</strong>
        </h4>
        <span className="text-light ">(*) Estos datos son obligatorios</span>
        <hr className="border border-secondary" />

        {content}
      </div>
    </div>
  );
};

export default NewAlumno;
