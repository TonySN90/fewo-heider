import { IoMdArrowBack } from "react-icons/io";
import { useNavigate } from "react-router";

function PageNotFound() {
  const navigate = useNavigate();
  return (
    <div>
      <p>Seite konnte nicht gefunden werden</p>
      <IoMdArrowBack onClick={() => navigate(-1)} />
    </div>
  );
}

export default PageNotFound;
