/* global lc_data, React */
/**
 * Dependencies.
 */
import { useDispatch, useSelector } from 'react-redux';
import { useState } from '@wordpress/element';
import axios from 'axios';
import CloseIcon from '../../../../../../../images/icons/close.svg';
import { isEmpty, map } from 'lodash';
import ModalDemo from '../../../../../../theme/packages/components/modal/ModalDemo';
import ReactSVG from 'react-svg';
import produce from 'immer';
import LoaderDashboardBookmarks from '../../../../../../theme/packages/components/loaders/LoaderDashboardBookmarks';

const MyBookmarks = (props) => {
  const dispatch = useDispatch();
  const data = useSelector(state => state);
  const { loading, business } = data;
  let { bookmarks } = business;
  const [errors, setErrors] = useState(null);
  const [fetching, setFetching] = useState(false);
  const [modalOpen, setModalOpen] = useState(false);
  const [bookmarkProducts, setBoomarkProducts] = useState(bookmarks);

  const removeBookmark = async (e, index, id) => {
    e.preventDefault();
    if (!confirm(lc_data.jst[637])) {
      return false;
    }
    if (lc_data.is_demo) {
      setModalOpen(true);
      return false;
    }
    setFetching(true);
    const nextState = produce(bookmarks, draftState => {
      draftState.splice(index, 1);
    });
    setBoomarkProducts(nextState);

    const headers = {
      'X-WP-Nonce': lc_data.nonce,
    };

    const formData = {
      action: 'bookmark',
      product_id: id,
    };

    await axios({
      credentials: 'same-origin',
      headers,
      method: 'post',
      url: `${lc_data.user_action}/bookmark`,
      data: formData,
    }).then(response => {
      setFetching(false);
    });
  };

  return (
    <section className="dashboard-account flex flex-col p-30 bg-white rounded shadow-theme">
      <div className="bookmarks flex flex-wrap -mx-col -mb-20">
        {loading && <LoaderDashboardBookmarks/>}
        {!loading && isEmpty(bookmarkProducts) && <p className="mb-20">{lc_data.jst[691]}</p>}
        {!loading &&
        !isEmpty(bookmarkProducts) && map(bookmarkProducts, (bookmark, index) => {
          return (
            <div key={index} className="mb-20 px-col w-full sm:w-1/3">
              <div className="relative flex flex-wrap shadow-theme rounded overflow-hidden">
                {bookmark.status === 'sold' && <div
                  className="overlay__sold-out absolute top-0 left-0 w-full h-full flex-center text-2xl font-bold text-white z-1">{lc_data.jst[694]}</div>}
                <button className="absolute top-0 right-0 flex-center p-6 bg-red-300 hover:bg-red-600"
                        style={{ borderBottomLeftRadius: 4, zIndex: 11 }}
                        onClick={(e) => removeBookmark(e, index, bookmark.id)}
                >
                  <ReactSVG
                    src={`${lc_data.dir}dist/${CloseIcon}`}
                    className={`w-12 h-12 fill-white`}
                  />
                </button>
                {bookmark.thumbnail &&
                <a href={bookmark.permalink} className="w-1/4" target="_blank">
                  <figure className="relative min-h-86"><img src={bookmark.thumbnail} alt={bookmark.title}
                                                             className="absolute top-0 left-0 w-full h-full object-cover"/>
                  </figure>
                </a>
                }
                <div className="p-20 bg-grey-100 w-3/4">
                  <a href={bookmark.permalink} target="_blank">
                    <h6 dangerouslySetInnerHTML={{ __html: bookmark.title }} style={{ fontSize: '15px' }}/>
                  </a>
                </div>
              </div>
            </div>
          );
        })}

        <ModalDemo
          isLogged={lc_data.logged_in}
          open={modalOpen}
          closeModal={() => setModalOpen(false)}
          title={lc_data.jst[606]}
        >
          <div className="font-semibold text-lg text-grey-700" dangerouslySetInnerHTML={{
            __html: lc_data.jst[607],
          }}
          />
        </ModalDemo>

      </div>
    </section>
  );
};

export default MyBookmarks;
