import { BrowserRouter, Navigate, Route, Routes } from "react-router-dom";
import Home from "./Pages/Home";
import { FewoProvider } from "./contexts/FewoContext";
import Impressum from "./Pages/Impressum";
import Datenschutz from "./Pages/Datenschutz";
import PageNotFound from "./Pages/PageNotFound";
import Cookiebot from "./components/Cookiebot";

export default function App() {
  return (
    <BrowserRouter>
      <FewoProvider>
        <Routes>
          <Route index element={<Navigate replace to="/home" />} />
          <Route path="/home" element={<Home />} />
          <Route path="/impressum" element={<Impressum />} />
          <Route path="/datenschutz" element={<Datenschutz />} />
          <Route path="*" element={<PageNotFound />} />
        </Routes>
        <Cookiebot />
      </FewoProvider>
    </BrowserRouter>
  );
}
