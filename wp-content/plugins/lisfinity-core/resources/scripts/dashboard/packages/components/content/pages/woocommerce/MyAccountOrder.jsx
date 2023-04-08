/* global lc_data, React */
/**
 * Dependencies.
 */
import { useDispatch, useSelector } from 'react-redux';
import * as actions from '../../../../store/actions';
import { useEffect, useState } from '@wordpress/element';
import { __, sprintf, _n } from '@wordpress/i18n';
import { map, isEmpty } from 'lodash';

const MyAccountOrder = (props) => {
  const dispatch = useDispatch();
  const data = useSelector(state => state);
  const { loading, business, menuOpen, profile } = data;
  const [order, setOrder] = useState(null);

  const getOrder = () => {
    const formData = {
      order: props.match.params.order,
    };
    const response = actions.fetchData(lc_data.get_wc_order, formData);
    response.then((result) => {
      setOrder(result.data);
    });
  };

  useEffect(() => {
    getOrder();
  }, []);

  return (
    !loading && order &&
    <section className="p-30 bg-white rounded shadow-theme w-full lg:w-11/16">

      <div className="w-full">
        <h3 className="mb-10 font-bold">{sprintf(lc_data.jst[269], order.id)}</h3>
      </div>

      <div
        dangerouslySetInnerHTML={{
          __html: order.order_status,
        }}
      />

      {!isEmpty(order.order_notes) &&
      <div className="notes--section mt-30">
        <h5 className="mb-10 font-semibold">{lc_data.jst[270]}</h5>

        <div className="notes flex flex-col">
          {map(order.order_notes, (note, index) => (
            <div key={index} className="mb-1 flex flex-col note w-full p-20 bg-grey-100 rounded">
              <b className="note--meta">
                {note.meta}
              </b>
              <p className="mt-4">{note.comment}</p>
            </div>
          ))}
        </div>
      </div>
      }

      {order && order.details &&
      <div
        dangerouslySetInnerHTML={{
          __html: order.details,
        }}
      />
      }

    </section>
  );
};

export default MyAccountOrder;
